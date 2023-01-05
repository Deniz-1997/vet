export const sdizCancelSign = async (thisRef): Promise<void> => {
  try {
    thisRef.isShowConfirmModal = false;
    thisRef.isLoading = true;

    if (typeof thisRef.rowValueAftClk.id === 'undefined') {
      throw new Error('Запись не имеет id');
    }

    await thisRef.$store.dispatch('agreementDocument/signDocumentFromDescription', {
      id: thisRef.rowValueAftClk.id,
      service: thisRef.cancelSignService,
    });

    const error = thisRef.$store.state.agreementDocument.agreementDocumentSign.error;
    if (error) {
      throw new Error(error);
    }
    thisRef.isLoading = false;
    thisRef.$notify({ group: 'sdiz', type: 'success', title: 'Запись аннулирована', text: '' });
    thisRef.$emit('update');
  } catch (err) {
    thisRef.$notify({ group: 'sdiz', type: 'error', title: 'Ошибка при формировании запроса', text: err || '' });
    thisRef.isLoading = false;
  }
};
