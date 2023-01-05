export function formatContragentName(data, showInn = true, showKpp = true) {
  const inn = data?.inn || '';
  const kpp = data?.kpp || '';

  const additionalInfo =
    '(' +
    (showInn ? `ИНН: ${inn || '-'}` : '') +
    `${showInn && showKpp ? ', ' : ''}` +
    (showKpp ? `КПП: ${kpp || '-'}` : '') +
    ')';

  return `${data?.short_name || data?.name || '-'} ` + additionalInfo;
}

export function formatContragent(data) {
  const nameToShow = formatContragentName(data);
  return { ...data, name: nameToShow };
}
