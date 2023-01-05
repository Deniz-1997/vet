import moment from 'moment';

type TDateOptions = {
  inputFormat?: string;
  outputFormat?: string;
};

export default function (
  date: string | Date,
  { inputFormat, outputFormat = 'DD.MM.YYYY hh:mm' }: TDateOptions = {}
): string {
  if (!date || !moment(date, inputFormat).isValid()) {
    return '';
  }

  return moment(date, inputFormat).format(outputFormat);
}
