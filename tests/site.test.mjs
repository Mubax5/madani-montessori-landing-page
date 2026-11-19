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

test('PPDB gives families distinct WhatsApp paths to consultation and a visit', () => {
  const html = readFileSync(builtPage('/ppdb/'), 'utf8');
  assert.match(html, /wa\.me\/6282123576275\?text=[^"]*konsultasi%20tentang%20PPDB/);
  assert.match(html, /wa\.me\/6282123576275\?text=[^"]*jadwal%20kunjungan/);
  assert.doesNotMatch(html, /<form(?:\s|>)/);
});

test('Beranda flows from the introduction through programs to contact', () => {
  const html = readFileSync(builtPage('/'), 'utf8');
  const titles = ['Mengenal<br', 'Cara anak belajar di Madani', 'Program Sekolah', 'Kegiatan Sekolah', 'Galeri', 'Penerimaan Peserta Didik Baru', 'Kontak &amp; Lokasi'];
  const main = html.slice(html.indexOf('<main'), html.indexOf('</main>'));
  const positions = titles.map((title) => main.indexOf(title));
  assert.doesNotMatch(main, /learning-steps|montessori-plate/);
  assert.equal([...main.matchAll(/<details name="program-beranda"/g)].length, 3);
  for (let index = 0; index < positions.length; index += 1) {
    assert.ok(positions[index] > (positions[index - 1] ?? -1), titles[index] + ' is out of order');
  }
});

test('the navbar marks the current page, including admissions and contact', () => {
  for (const route of ['/ppdb/', '/kontak/', '/program/program-unggulan/']) {
    const html = readFileSync(builtPage(route), 'utf8');
    assert.match(html, new RegExp('href="' + route + '" aria-current="page"'));
  }
});
