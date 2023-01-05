import moment from 'moment';

export const mapInnerForm = (response) => {
  const content = response.content.map((item) => ({
    ...item,
    okpd2: item.okpd2 || { ...item },
    code: item.okpd2 && !!item.okpd2.length ? item.okpd2.code : item.code,
    is_product_ru: item.is_product ? 'Да' : 'Нет',
    is_grain_ru: item.is_grain ? 'Да' : 'Нет',
    is_actual: item.end_date ? !(moment(item.end_date, 'DD.MM.YYYY') <= moment()) : true,
  }));

  return { ...response, content };
};

export const mapForm = (data) => {
  return {
    ...data,
    purposes: data.purposes?.map((item) => {
      return {
        ...item,
        disabled: true,
      };
    }),
  };
};
