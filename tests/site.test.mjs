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
  '/kontak/',
  '/kebijakan-privasi/',
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
    assert.match(html, /<footer\b/);
    assert.doesNotMatch(html, /(?:classroom-preview|montessori-study|botanical)\.[\w-]+/);

    for (const match of html.matchAll(/<a\s[^>]*href="(\/(?!\/)[^"]*)"/g)) {
      const path = new URL(match[1], 'https://local.invalid').pathname;
      assert.ok(existsSync(builtPage(path)), route + ' has a broken link to ' + path);
    }
  });
}

test('PPDB keeps the interest form on-site and offers consultation first', () => {
  const html = readFileSync(builtPage('/ppdb/'), 'utf8');
  assert.ok(html.indexOf('href="/kontak/#konsultasi"') < html.indexOf('href="#formulir-minat"'));
  assert.match(html, /id="formulir-minat"/);
  assert.match(html, /<form[^>]*data-admissions-form/);
  assert.match(html, /pattern="\.\*\\S\.\*"/);
  assert.match(html, /Lanjutkan ke WhatsApp/);
  assert.doesNotMatch(html, /madanimontessori\.my\.id/);

  const contact = readFileSync(builtPage('/kontak/'), 'utf8');
  assert.match(contact, /id="konsultasi"/);
  assert.match(contact, /id="lokasi"/);
  assert.match(contact, /wa\.me\/6282123576275\?text=[^"]*konsultasi%20tentang%20PPDB/);
  assert.match(contact, /wa\.me\/6282123576275\?text=[^"]*jadwal%20kunjungan/);
});

test('Beranda introduces the school and links to programs, activities, and admissions', () => {
  const html = readFileSync(builtPage('/'), 'utf8');
  const main = html.slice(html.indexOf('<main'), html.indexOf('</main>'));
  const hero = main.slice(0, main.indexOf('</section>'));
  const sectionHeadings = ['pendekatan', 'program-title', 'gallery-title', 'admissions-title', 'contact-title'];
  const positions = sectionHeadings.map((heading) => main.indexOf('id="' + heading + '"'));

  assert.match(hero, /TK Islam Terpadu/);
  assert.match(hero, /<h1 id="home-title">Madani Montessori<\/h1>/);
  assert.match(hero, /href="\/ppdb\/"[^>]*>Lihat informasi PPDB</);
  assert.match(hero, /href="#pendekatan"[^>]*>Kenali sekolah</);
  assert.match(hero, /<figcaption>Ilustrasi sementara/);
  assert.match(main, /href="\/agenda\/"[^>]*>Lihat arsip kegiatan</);
  assert.match(main, /href="\/program\/#perbandingan"/);
  assert.match(main, /<dialog[^>]*data-gallery-dialog/);
  assert.match(main, /data-gallery-photo/);
  assert.equal([...html.matchAll(/class="section-edge"/g)].length, 1);

  for (const [name, hours] of [['Reguler', '07.30–10.30'], ['Half Day', '07.30–14.00'], ['Full Day', '07.30–16.00']]) {
    assert.ok(main.includes('<h3>' + name + '</h3>'));
    assert.ok(main.includes(hours + ' WIB'));
  }
  for (let index = 0; index < positions.length; index += 1) {
    assert.ok(positions[index] > (positions[index - 1] ?? -1), sectionHeadings[index] + ' is out of order');
  }
});

test('the navbar marks the current page, including admissions and contact', () => {
  for (const route of ['/ppdb/', '/kontak/', '/program/program-unggulan/']) {
    const html = readFileSync(builtPage(route), 'utf8');
    assert.match(html, new RegExp('href="' + route + '" aria-current="page"'));
  }
  const profile = readFileSync(builtPage('/profil/'), 'utf8');
  assert.doesNotMatch(profile.slice(profile.indexOf('<header'), profile.indexOf('</header>')), /submenu-toggle/);
  assert.match(profile, /aria-label="Halaman Profil"/);
  assert.match(profile, /href="\/profil\/visi-misi\/"/);
  assert.match(profile, /href="\/metode-montessori\/"/);
});

test('Program places each plan in a table column with shared comparison rows', () => {
  const html = readFileSync(builtPage('/program/'), 'utf8');
  assert.match(html, /<table class="comparison"/);
  assert.match(html, /<th scope="col">Reguler<\/th>\s*<th scope="col">Half Day<\/th>\s*<th scope="col">Full Day<\/th>/);
  for (const row of ['Jam sekolah', 'Kegiatan', 'Keunggulan', 'Jam makan', 'Jumlah makan']) {
    assert.ok(html.includes('<th scope="row">' + row + '</th>'), 'Missing comparison row ' + row);
  }
});
