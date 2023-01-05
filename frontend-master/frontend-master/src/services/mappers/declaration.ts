/* eslint-disable max-nested-callbacks */
import { Mapper } from '@/utils';
import { TDeclarationLogRequestItem } from '@/services/models/declaration';

export class DeclarationItem extends Mapper<TDeclarationLogRequestItem> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get number() {
    return this.get(({ declaration_number }) => declaration_number).required.value;
  }

  @Mapper.catch()
  get exportDate() {
    return this.get(({ export_date, start_date }) => export_date || start_date).required.date().value;
  }

  @Mapper.catch()
  get inn() {
    return this.get(({ inn }) => inn).required.value;
  }

  @Mapper.catch()
  get type() {
    return this.get(({ is_temp }) => (is_temp ? 'Временная' : 'Постоянная')).required.value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ status }) => status.name).required.value;
  }

  @Mapper.catch()
  get info() {
    return this.get(({ custom_declaration_info }) =>
      (custom_declaration_info || []).map((_item, index) => ({
        id: this.get(({ custom_declaration_info }) => custom_declaration_info[index].declaration_info_id).required
          .value,
        productName: this.get(({ custom_declaration_info }) => custom_declaration_info[index].product_name).required
          .value,
        product: {
          name: this.get(({ custom_declaration_info }) => custom_declaration_info[index].product_name).required.value,
          quantity: (() => {
            const count = this.get(({ custom_declaration_info }) => custom_declaration_info[index].product_count)
              .required.value;
            const unit = this.get(({ custom_declaration_info }) => custom_declaration_info[index].measure_unit).required
              .value;

            return `${count || 0} ${unit || 'шт.'}`;
          })(),
        },
        sender: this.optional(() => ({
          name: this.get(({ custom_declaration_info }) => custom_declaration_info[index].sender_name).optional.value,
          inn: this.get(({ custom_declaration_info }) => custom_declaration_info[index].sender_inn).optional.value,
          kpp: this.get(({ custom_declaration_info }) => custom_declaration_info[index].sender_kpp).optional.value,
        })),
        recipient: this.optional(() => ({
          name: this.get(({ custom_declaration_info }) => custom_declaration_info[index].recipient_name).optional.value,
          inn: this.get(({ custom_declaration_info }) => custom_declaration_info[index].recipient_inn).optional.value,
          kpp: this.get(({ custom_declaration_info }) => custom_declaration_info[index].recipient_kpp).optional.value,
        })),
        tnved: this.get(({ custom_declaration_info }) => custom_declaration_info[index].tnved_code).required.value,
      }))
    ).required.value;
  }

  @Mapper.catch()
  get content() {
    return this.get(({ xml_content }) => xml_content).optional.value;
  }

  toJSON() {
    return {
      id: this.id,
      number: this.number,
      exportDate: this.exportDate,
      inn: this.inn,
      status: this.status,
      type: this.type,
      info: this.info,
      content: this.content,
    };
  }
}
