import { Mapper } from '@/utils';
import { ISubjectData } from '@/services/models/common';

const getName = ({ name, short_name, inn, kpp }) => {
  if (inn) {
    return `${name || short_name} ${inn ? ' (' + inn + (kpp ? '/' + kpp : '') + ')' : ''}`;
  }
  return name || short_name;
};

export class SubjectItem extends Mapper<ISubjectData> {
  @Mapper.catch()
  get id() {
    return this.get(({ subject_id }) => subject_id).required.value;
  }

  @Mapper.catch()
  get name() {
    return this.get(({ name, short_name, inn, kpp }) => getName({ name, short_name, inn, kpp })).required.value;
  }

  @Mapper.catch()
  get inn() {
    return this.get(({ inn }) => inn).optional.value;
  }

  @Mapper.catch()
  get ogrn() {
    return this.get(({ ogrn }) => ogrn).optional.value;
  }

  @Mapper.catch()
  get kpp() {
    return this.get(({ kpp }) => kpp).optional.value;
  }

  toJSON() {
    return {
      id: this.id,
      name: this.name,
      inn: this.inn,
      ogrn: this.ogrn,
      kpp: this.kpp,
    };
  }
}
