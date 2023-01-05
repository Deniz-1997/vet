export const mapInnerForm = (data: any) => {
  return {
    ...data,
    subject: {
      ...data.subject,
      identity_doc: {
        type: data.subject?.identity_doc?.type ?? null,
        series: data.subject?.identity_doc?.series ?? null,
        id_number: data.subject?.identity_doc?.id_number ?? null,
        doc_date: data.subject?.identity_doc?.doc_date ?? null,
      },
    },
  };
};
