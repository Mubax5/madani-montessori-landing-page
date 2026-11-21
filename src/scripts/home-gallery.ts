const gallery = document.querySelector<HTMLElement>('[data-home-gallery]');
const dialog = gallery?.querySelector<HTMLDialogElement>('[data-gallery-dialog]');
const dialogImage = dialog?.querySelector<HTMLImageElement>('[data-dialog-image]');
const dialogTitle = dialog?.querySelector('#gallery-photo-title');
const dialogDescription = dialog?.querySelector('#gallery-photo-description');
const closeButton = dialog?.querySelector<HTMLButtonElement>('.gallery-close');

if (gallery && dialog?.showModal && dialogImage && dialogTitle && dialogDescription && closeButton) {
  let opener: HTMLAnchorElement | undefined;

  for (const link of gallery.querySelectorAll<HTMLAnchorElement>('[data-gallery-photo]')) {
    link.setAttribute('aria-haspopup', 'dialog');
    link.addEventListener('click', (event) => {
      if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;

      event.preventDefault();
      opener = link;
      dialogTitle.textContent = link.dataset.title ?? '';
      dialogDescription.textContent = link.dataset.description ?? '';
      dialogImage.alt = link.querySelector('img')?.alt ?? '';
      dialogImage.src = link.href;
      dialog.showModal();
    });
  }

  closeButton.addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', (event) => {
    if (event.target !== dialog) return;

    const { top, right, bottom, left } = dialog.getBoundingClientRect();
    if (event.clientX < left || event.clientX > right || event.clientY < top || event.clientY > bottom) {
      dialog.close();
    }
  });
  dialog.addEventListener('close', () => opener?.focus({ preventScroll: true }));
}
