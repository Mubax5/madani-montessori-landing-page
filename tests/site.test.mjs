import assert from 'node:assert/strict';
import { existsSync, readFileSync } from 'node:fs';
import { join } from 'node:path';
import { test } from 'node:test';

const dist = new URL('../dist/', import.meta.url);
const routes = [
  '/',
  '/profil/',
  '/profil/visi-misi/',
  '/metode-montessori/',
  '/program/',
  '/program/program-sekolah/',
  '/program/program-unggulan/',
  '/agenda/',
  '/agenda/annual-celebration/',
  '/galeri/',
  '/ppdb/',
  '/404.html',
];

function builtPage(path) {
  const filename = path.endsWith('/') ? join(path.slice(1), 'index.html') : path.slice(1);
  return new URL(filename.replaceAll('\\', '/'), dist);
}

for (const route of routes) {
  test(route + ' is a complete page and its local links resolve', () => {
    const html = readFileSync(builtPage(route), 'utf8');
    assert.match(html, /<html lang="id"/);
    assert.equal([...html.matchAll(/<h1(?:\s|>)/g)].length, 1);
    assert.match(html, /<footer[^>]*id="info-sekolah"/);
    assert.doesNotMatch(html, /<figcaption\b|href="\/kontak\//);
    assert.doesNotMatch(html, /(?:classroom-preview|montessori-study|botanical)\.[\w-]+/);

    for (const match of html.matchAll(/<a\s[^>]*href="(\/(?!\/)[^"]*)"/g)) {
      const path = new URL(match[1], 'https://local.invalid').pathname;
      assert.ok(existsSync(builtPage(path)), route + ' has a broken link to ' + path);
    }
  });
}

test('PPDB keeps the interest form on-site and offers consultation first', () => {
  const html = readFileSync(builtPage('/ppdb/'), 'utf8');
  const consultation = html.indexOf('konsultasi%20tentang%20PPDB');
  assert.ok(consultation > -1 && consultation < html.indexOf('href="#formulir-minat"'));
  assert.match(html, /id="formulir-minat"/);
  assert.match(html, /<form[^>]*data-admissions-form/);
  assert.match(html, /pattern="\.\*\\S\.\*"/);
  assert.match(html, /Lanjutkan ke WhatsApp/);
  assert.doesNotMatch(html, /madanimontessori\.my\.id/);
});

test('Beranda introduces the school and links to programs, the gallery, and admissions', () => {
  const html = readFileSync(builtPage('/'), 'utf8');
  const main = html.slice(html.indexOf('<main'), html.indexOf('</main>'));
  const hero = main.slice(0, main.indexOf('</section>'));
  const sectionHeadings = ['pendekatan', 'program-title', 'gallery-title', 'admissions-title'];
  const positions = sectionHeadings.map((heading) => main.indexOf('id="' + heading + '"'));

  assert.match(hero, /TK Islam Terpadu/);
  assert.match(hero, /<h1 id="home-title">Madani Montessori<\/h1>/);
  assert.match(hero, /href="\/ppdb\/"[^>]*>Lihat informasi PPDB</);
  assert.match(hero, /href="#pendekatan"[^>]*>Kenali sekolah</);
  assert.match(main, /href="\/galeri\/"[^>]*>Lihat galeri</);
  assert.match(main, /href="\/program\/#perbandingan"/);
  assert.equal([...html.matchAll(/class="section-edge"/g)].length, 1);

  for (let index = 0; index < positions.length; index += 1) {
    assert.ok(positions[index] > (positions[index - 1] ?? -1), sectionHeadings[index] + ' is out of order');
  }
});

test('one navbar exposes the Profil and Program destinations and marks the current link', () => {
  for (const route of ['/profil/', '/metode-montessori/', '/program/', '/program/program-unggulan/', '/ppdb/']) {
    const html = readFileSync(builtPage(route), 'utf8');
    const header = html.slice(html.indexOf('<header'), html.indexOf('</header>'));
    assert.equal([...header.matchAll(/<details class="nav-group"/g)].length, 2);
    assert.match(header, new RegExp('href="' + route + '" aria-current="page"'));
    assert.match(header, /href="\/profil\/visi-misi\/"/);
    assert.match(header, /href="\/program\/program-sekolah\/"/);
    assert.doesNotMatch(html, /class="section-nav"/);
  }
});

test('Program accordions keep every name and hour visible and the details page lists activities and meals', () => {
  for (const route of ['/', '/program/program-sekolah/']) {
    const html = readFileSync(builtPage(route), 'utf8');
    const summaries = Array.from(html.matchAll(/<details name="program-sekolah"[^>]*>\s*<summary[^>]*>([\s\S]*?)<\/summary>/g), (match) => match[1]);
    assert.equal(summaries.length, 3, route);
    for (const [index, name, hours] of [[0, 'Reguler', '07.30–10.30'], [1, 'Half Day', '07.30–14.00'], [2, 'Full Day', '07.30–16.00']]) {
      assert.ok(summaries[index].includes('>' + name + '</span>'));
      assert.ok(summaries[index].includes(hours + ' WIB</span>'));
    }
  }
  const details = readFileSync(builtPage('/program/program-sekolah/'), 'utf8');
  assert.match(details, /<dt[^>]*>Kegiatan<\/dt><dd[^>]*>Montessori, bahasa, dan motorik.<\/dd>/);
  assert.match(details, /<dt[^>]*>Layanan makan<\/dt><dd[^>]*>Makan siang<\/dd>/);
  assert.match(details, /href="\/program\/#perbandingan"/);
  assert.doesNotMatch(details, /<table class="comparison"/);
});

test('Program presents choices, an open comparison, and both PPDB paths in sequence', () => {
  const html = readFileSync(builtPage('/program/'), 'utf8');
  const chooser = html.indexOf('<fieldset class="program-choices"');
  const comparison = html.indexOf('id="perbandingan"');
  const admissions = html.indexOf('id="lanjut-ppdb"');
  assert.ok(chooser >= 0 && chooser < comparison && comparison < admissions);
  for (const [name, hours] of [['Reguler', '07.30–10.30'], ['Half Day', '07.30–14.00'], ['Full Day', '07.30–16.00']]) {
    assert.ok(html.slice(chooser, comparison).includes(hours + ' WIB'));
    assert.ok(html.slice(chooser, comparison).includes('value="' + name + '"'));
    assert.ok(html.slice(comparison, admissions).includes('data-comparison-program="' + name + '"'));
  }
  assert.match(html, /href="#perbandingan"/);
  assert.match(html, /<table class="comparison"/);
  assert.doesNotMatch(html, /<details id="perbandingan"/);
  assert.match(html, /data-program-consultation href="https:\/\/wa\.me\//);
  assert.match(html, /data-program-form href="\/ppdb\/#formulir-minat"/);
  assert.match(html, /Jam makan dan jumlah makan untuk semua program/);
});

test('gallery on Beranda and Galeri shares caption-free slides and accessible controls', () => {
  for (const route of ['/', '/galeri/']) {
    const html = readFileSync(builtPage(route), 'utf8');
    assert.equal([...html.matchAll(/<div[^>]*\bdata-gallery-carousel\b/g)].length, 1, route);
    assert.equal([...html.matchAll(/<div[^>]*\bdata-gallery-slide\b/g)].length, 3, route);
    assert.match(html, /aria-label="Tampilkan gambar 1" aria-current="true"/);
    assert.match(html, /data-gallery-playback[^>]*aria-label="Jeda putar otomatis"/);
    assert.doesNotMatch(html, /data-gallery-dialog|<figcaption\b/);
  }
});

test('school location and contact details share the footer and the old contact route redirects there', () => {
  const home = readFileSync(builtPage('/'), 'utf8');
  const main = home.slice(home.indexOf('<main'), home.indexOf('</main>'));
  assert.doesNotMatch(main, /Kontak (?:&amp;|dan) lokasi|home-contact|mailto:/i);
  const footer = home.slice(home.indexOf('<footer'));
  assert.match(footer, /wa\.me\/6282123576275\?text=[^"]*jadwal%20kunjungan/);
  assert.match(footer, /Cisauk, Kabupaten Tangerang/);
  assert.match(footer, /href="mailto:info@madanimontessori\.sch\.id"/);
  assert.match(footer, /ilustrasi sementara/);
  const oldContact = readFileSync(builtPage('/kontak/'), 'utf8');
  assert.match(oldContact, /http-equiv="refresh" content="0;url=\/#info-sekolah"/);
});

test('the retired privacy route is absent and the custom 404 provides recovery links', () => {
  assert.ok(!existsSync(builtPage('/kebijakan-privasi/')));
  for (const route of routes) {
    const html = readFileSync(builtPage(route), 'utf8');
    assert.doesNotMatch(html, /href="\/kebijakan-privasi/);
  }
  const missing = readFileSync(builtPage('/404.html'), 'utf8');
  assert.match(missing, /<h1>Halaman tidak ditemukan<\/h1>/);
  assert.match(missing, /href="\/"[^>]*>Kembali ke Beranda/);
  assert.match(missing, /href="\/program\/"[^>]*>Lihat program/);
});
