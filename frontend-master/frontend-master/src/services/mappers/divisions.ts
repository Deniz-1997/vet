/* eslint-disable max-lines-per-function */
import isEmpty from 'lodash/isEmpty';
import { Mapper } from '@/utils';
import { IDivisionsItemResponse } from '@/services/models/divisions';

export class DivisionsItemIn extends Mapper<IDivisionsItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ subject_division_id }) => subject_division_id).required.value;
  }

  @Mapper.catch()
  get parent_division() {
    return this.get(({ parent_division }) => {
      return !isEmpty(parent_division) && new DivisionsItemIn(parent_division).toJSON();
    }).optional.value;
  }

  @Mapper.catch()
  get name() {
    return this.get(({ subject, name }) => name || subject.short_name || subject.name).required.value;
  }

  @Mapper.catch()
  get division_staff_user_full_names() {
    return this.get(({ division_staff_user_full_names }) => division_staff_user_full_names).required.value;
  }

  @Mapper.catch()
  get staff() {
    return this.get(({ division_staff, division_staff_user_full_names }) => {
      const result: any = (division_staff || []).map((item) => ({
        id: item.user_id,
        name: item.user_full_name,
      }));

      result.employees = division_staff_user_full_names || result.map(({ name }) => name).join(', ');

      return result;
    }).required.value;
  }

  toJSON() {
    return {
      id: this.id,
      name: this.name,
      parent_division: this.parent_division,
      staff: this.staff,
      division_staff_user_full_names: this.division_staff_user_full_names,
    };
  }
}

// export class DivisionsItemOut extends Mapper<TMapperPlain<DivisionsItemIn>> implements IDivisionsItemRequestOut {

//   @Mapper.catch()
//   get subject_id() {
//     return this.get(({ subjectId }) => subjectId).optional.value;
//   }

//   toJSON() {
//     return {
//       id: this.id,
//       subject_id: this.subject_id,
//       subject_data: this.optional(() => ({
//         subject_id: this.get(({ subjectId }) => subjectId).optional.value,
//         // subject_data_id: this.get(({ subjectDataId }) => subjectDataId).optional.value,
//         // first_name: this.get(({ firstName }) => firstName).optional.value,
//         // last_name: this.get(({ lastName }) => lastName).optional.value,
//         // second_name: this.get(({ secondName }) => secondName).optional.value,
//         // nza: this.get(({ nza }) => nza).optional.value,
//         // email: this.get(({ email }) => email).optional.value,
//         // phone_number: this.get(({ phoneNumber }) => phoneNumber).optional.value,
//         inn: this.get(({ inn }) => inn).optional.value,
//         kpp: this.get(({ kpp }) => kpp).optional.value,
//         ogrn: this.get(({ ogrn }) => ogrn).optional.value,
//         opf: this.get(({ opf }) => opf).optional.value,
//         // short_name: this.get(({ shortName }) => shortName).optional.value,
//         // country: this.get(({ country }) => country).optional.value,
//         name: this.get(({ name }) => name).optional.value,
//       })) as ISubjectData,
//     };
//   }
// }
