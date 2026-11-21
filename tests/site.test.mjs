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

test('Beranda flows from the introduction through programs to contact', () => {
  const html = readFileSync(builtPage('/'), 'utf8');
  const titles = ['Mengenal<br', 'Program Sekolah', 'id="gallery-title">Galeri', 'Penerimaan Peserta Didik', 'Kontak &amp; Lokasi'];
  const main = html.slice(html.indexOf('<main'), html.indexOf('</main>'));
  const positions = titles.map((title) => main.indexOf(title));
  assert.doesNotMatch(main, /Cara anak belajar di Madani|Kegiatan Sekolah/);
  assert.match(main, /class="hero-figure"/);
  assert.match(main, /<span class="hero-school-level">TK IT<\/span>/);
  assert.match(main, /class="section-edge section-edge--bottom"/);
  assert.equal([...main.matchAll(/<details name="program-beranda"/g)].length, 3);
  assert.match(main, /<dialog[^>]*data-gallery-dialog/);
  assert.match(main, /data-gallery-photo/);
  assert.doesNotMatch(main, /Buka Galeri|Foto sebelumnya|Foto berikutnya/);
  for (let index = 0; index < positions.length; index += 1) {
    assert.ok(positions[index] > (positions[index - 1] ?? -1), titles[index] + ' is out of order');
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
