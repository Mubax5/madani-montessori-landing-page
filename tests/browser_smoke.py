import os

from playwright.sync_api import sync_playwright, expect


BASE_URL = os.environ.get("BASE_URL", "http://127.0.0.1:8012")
PUBLIC_PATHS = [
    "/",
    "/tentang",
    "/program-sekolah",
    "/program-unggulan",
    "/bimbel",
    "/training-parenting",
    "/galeri",
    "/kontak",
]


def assert_no_broken_images(page):
    broken = page.evaluate(
        """() => Array.from(document.images)
            .filter((img) => !img.complete || img.naturalWidth === 0)
            .map((img) => img.getAttribute('src'))"""
    )
    assert broken == [], f"Broken images: {broken}"


with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page(viewport={"width": 390, "height": 844})
    console_errors = []
    page.on("console", lambda message: console_errors.append(message.text) if message.type == "error" else None)

    for path in PUBLIC_PATHS:
        response = page.goto(f"{BASE_URL}{path}", wait_until="networkidle")
        assert response and response.status == 200, f"{path} returned {response.status if response else 'no response'}"
        expect(page.locator("h1")).to_be_visible()
        assert_no_broken_images(page)

    page.goto(f"{BASE_URL}/", wait_until="networkidle")
    page.get_by_role("button", name="Buka menu").click()
    assert "is-open" in (page.locator("#site-navigation").get_attribute("class") or "")

    page.goto(f"{BASE_URL}/galeri", wait_until="networkidle")
    page.get_by_role("button", name="Event").click()
    visible_cards = page.locator("[data-gallery-grid] [data-category]:visible").count()
    assert visible_cards >= 1, "Gallery event filter should show at least one card"

    page.goto(f"{BASE_URL}/admin", wait_until="networkidle")
    expect(page.locator("input[type='email']")).to_be_visible()

    desktop = browser.new_page(viewport={"width": 1366, "height": 900})
    desktop.goto(f"{BASE_URL}/", wait_until="networkidle")
    expect(desktop.locator("h1")).to_be_visible()
    assert_no_broken_images(desktop)

    browser.close()

    assert console_errors == [], f"Console errors: {console_errors}"
