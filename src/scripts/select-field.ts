for (const field of document.querySelectorAll<HTMLElement>('[data-select-field]')) {
  const label = field.querySelector('label');
  const select = field.querySelector<HTMLSelectElement>('[data-select-native]');
  const picker = field.querySelector<HTMLElement>('[data-select-picker]');
  const trigger = picker?.querySelector<HTMLButtonElement>('[role="combobox"]');
  const list = picker?.querySelector<HTMLElement>('[role="listbox"]');
  const value = trigger?.querySelector<HTMLElement>('[data-select-value]');
  const options = [...(list?.querySelectorAll<HTMLElement>('[role="option"]') ?? [])];
  if (!label || !select || !picker || !trigger || !list || !value || !options.length) continue;

  let active = 0;
  let query = '';
  let lastKeyAt = 0;
  const sync = () => {
    const selected = Math.max(0, select.selectedIndex);
    value.textContent = options[selected].textContent?.trim() ?? '';
    options.forEach((option, index) => option.setAttribute('aria-selected', String(index === selected)));
  };
  const highlight = (index: number, scroll = true) => {
    options[active].removeAttribute('data-highlighted');
    active = Math.min(Math.max(index, 0), options.length - 1);
    options[active].setAttribute('data-highlighted', '');
    trigger.setAttribute('aria-activedescendant', options[active].id);
    if (scroll) options[active].scrollIntoView({ block: 'nearest', behavior: 'instant' });
  };
  const open = (index: number) => {
    query = '';
    list.hidden = false;
    trigger.setAttribute('aria-expanded', 'true');
    highlight(index, false);
  };
  const close = (commit = false) => {
    if (commit && select.selectedIndex !== active) {
      select.selectedIndex = active;
      select.dispatchEvent(new Event('change', { bubbles: true }));
    }
    list.hidden = true;
    trigger.setAttribute('aria-expanded', 'false');
    trigger.removeAttribute('aria-activedescendant');
  };

  sync();
  label.htmlFor = trigger.id;
  select.hidden = true;
  picker.hidden = false;
  select.addEventListener('change', sync);
  select.form?.addEventListener('reset', () => window.requestAnimationFrame(sync));

  trigger.addEventListener('click', () => {
    trigger.focus({ preventScroll: true });
    if (list.hidden) open(select.selectedIndex);
    else close();
  });
  trigger.addEventListener('keydown', (event) => {
    const { key } = event;
    if (key === 'Tab') {
      if (!list.hidden) close(true);
      return;
    }
    if (key === 'Escape' && !list.hidden) {
      event.preventDefault();
      close();
      return;
    }
    if ((key === 'Enter' || key === ' ') && !list.hidden) {
      event.preventDefault();
      close(true);
      return;
    }
    if (['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(key)) {
      event.preventDefault();
      if (list.hidden) open(key === 'End' ? options.length - 1 : key === 'Home' ? 0 : select.selectedIndex);
      else if (key === 'Home' || key === 'End') highlight(key === 'Home' ? 0 : options.length - 1);
      else highlight(active + (key === 'ArrowDown' ? 1 : -1));
      return;
    }
    if (key.length !== 1 || key === ' ' || event.ctrlKey || event.altKey || event.metaKey) return;
    if (list.hidden) open(select.selectedIndex);
    const now = Date.now();
    const letter = key.toLocaleLowerCase('id');
    query = now - lastKeyAt < 650 ? query + letter : letter;
    lastKeyAt = now;
    const matching = options.findIndex((option) => option.textContent?.trim().toLocaleLowerCase('id').startsWith(query));
    if (matching >= 0) highlight(matching);
  });

  list.addEventListener('mousedown', (event) => event.preventDefault());
  options.forEach((option, index) => {
    option.addEventListener('click', () => {
      highlight(index, false);
      close(true);
      trigger.focus({ preventScroll: true });
    });
  });
  field.addEventListener('focusout', (event) => {
    if (!(event.relatedTarget instanceof Node) || !field.contains(event.relatedTarget)) close();
  });
  document.addEventListener('pointerdown', (event) => {
    if (event.target instanceof Node && !field.contains(event.target)) close();
  });
}
