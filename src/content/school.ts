import type { ImageMetadata } from 'astro';
import classroomIllustration from '../assets/gallery-classroom-illustration.webp';
import practicalLifeIllustration from '../assets/gallery-practical-life-illustration.webp';

export const school = {
  name: 'Madani Montessori',
  publishedName: 'TKIT Madani Montessori',
  location: 'Cisauk, Kabupaten Tangerang',
  phone: '6282123576275',
  displayPhone: '0821-2357-6275',
  email: 'info@madanimontessori.sch.id',
  introduction:
    'Kenali lingkungan belajar, program sekolah, dan pendekatan Montessori Islami di Madani Montessori.',
};

export const admissionsInvitation = 'Kami mengundang Anda untuk mengenal program Madani Montessori dan berkonsultasi mengenai proses penerimaan.';

export const navigation = [
  { label: 'Beranda', href: '/' },
  {
    label: 'Profil',
    href: '/profil/',
    children: [
      { label: 'Visi & Misi', href: '/profil/visi-misi/' },
      { label: 'Metode Montessori', href: '/metode-montessori/' },
    ],
  },
  {
    label: 'Program',
    href: '/program/',
    children: [
      { label: 'Program Sekolah', href: '/program/program-sekolah/' },
      { label: 'Program Unggulan', href: '/program/program-unggulan/' },
    ],
  },
  { label: 'Kegiatan', href: '/agenda/' },
  { label: 'Galeri', href: '/galeri/' },
  { label: 'PPDB', href: '/ppdb/' },
  { label: 'Kontak', href: '/kontak/' },
];

export function findNavigationSection(path: string) {
  return navigation.find((item) => path === item.href
    || (item.href !== '/' && path.startsWith(item.href))
    || item.children?.some((link) => path === link.href));
}

export const schoolPrograms = [
  {
    name: 'Reguler',
    hours: '07.30–10.30',
    summary: 'Kegiatan inti pada pagi hari, termasuk Montessori, bahasa, dan motorik.',
    activities: 'Montessori, bahasa, dan motorik.',
    scope: 'Sesi inti pada pagi hari.',
    meals: 'Perlu konfirmasi',
  },
  {
    name: 'Half Day',
    hours: '07.30–14.00',
    summary: 'Sesi belajar lebih panjang, dengan makan siang dan waktu istirahat.',
    activities: 'Sesi belajar, makan siang, dan istirahat.',
    scope: 'Waktu belajar berlanjut hingga awal siang.',
    meals: 'Makan siang',
  },
  {
    name: 'Full Day',
    hours: '07.30–16.00',
    summary: 'Kegiatan, makan, istirahat, dan pendampingan anak hingga sore hari.',
    activities: 'Kegiatan, makan, dan istirahat.',
    scope: 'Pendampingan hingga sore.',
    meals: 'Termasuk waktu makan',
  },
];

export const featuredPrograms = [
  {
    title: 'Montessori Practical Life',
    description: 'Latihan sehari-hari seperti menuang, memilah, dan merapikan alat.',
  },
  {
    title: 'Adab dan doa harian',
    description: 'Pembiasaan salam, doa, bergiliran, dan menghargai teman.',
  },
  {
    title: 'Pengenalan tahsin dan hafalan',
    description: 'Pengenalan huruf hijaiyah, doa pilihan, dan surat pendek secara bertahap.',
  },
];

export const practicalLife = {
  title: 'Belajar menuang',
  summary:
    'Menuang adalah salah satu kegiatan Practical Life yang diperkenalkan Madani. Lewat satu pekerjaan sehari-hari, anak berlatih mengendalikan gerakan dan merapikan kembali alatnya.',
};

export const activities = [
  {
    slug: 'annual-celebration',
    title: 'Annual Celebration',
    period: 'Juni 2026',
    description:
      'Undangan sekolah mencatat susunan acara yang mencakup tilawah, prosesi wisuda, dan penampilan siswa.',
    sections: [
      'Acara dibuka dengan registrasi, pembukaan, menyanyikan Indonesia Raya, dan tilawah.',
      'Rangkaian dalam undangan berlanjut dengan sambutan, prosesi wisuda, doa, dan penampilan siswa.',
    ],
  },
];

export interface GalleryPhoto {
  image: ImageMetadata;
  alt: string;
  caption: string;
  description?: string;
  category: string;
}

// Add only photographs cleared for public use by the school.
export const galleryPhotos: GalleryPhoto[] = [];

export const galleryIllustrations: GalleryPhoto[] = [
  {
    image: classroomIllustration,
    alt: 'Ilustrasi ruang dengan rak rendah berisi alat peraga Montessori',
    caption: 'Ruang belajar Montessori',
    description: 'Rak rendah menata alat peraga dalam jangkauan anak. Setiap alat memiliki tempat sehingga mudah dipilih dan dikembalikan.',
    category: 'Ilustrasi',
  },
  {
    image: practicalLifeIllustration,
    alt: 'Ilustrasi anak menuang air di atas baki alat peraga',
    caption: 'Latihan menuang',
    description: 'Anak berlatih menuang air dari kendi kecil dengan gerakan terkendali. Baki membantu menjaga alat dan area kerja tetap rapi.',
    category: 'Ilustrasi',
  },
];

export interface HeroPhoto {
  image: ImageMetadata;
  alt: string;
  caption: string;
  position?: string;
  mobilePosition?: string;
}

type SchoolPhoto = Omit<GalleryPhoto, 'category'>;
export const schoolMedia: {
  hero?: HeroPhoto;
  materials?: SchoolPhoto;
  profile?: SchoolPhoto;
  practicalLife?: SchoolPhoto;
} = {};

const whatsappMessages = {
  contact: 'Assalamualaikum, saya ingin bertanya tentang Madani Montessori.',
  admissions: 'Assalamualaikum, saya ingin konsultasi tentang PPDB Madani Montessori.',
  visit: 'Assalamualaikum, saya ingin menanyakan jadwal kunjungan ke Madani Montessori.',
};

export function whatsappLink(purpose: keyof typeof whatsappMessages) {
  return 'https://wa.me/' + school.phone + '?text=' + encodeURIComponent(whatsappMessages[purpose]);
}
