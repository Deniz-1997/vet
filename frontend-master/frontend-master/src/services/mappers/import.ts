import { Mapper } from '@/utils';
import { TImportInnerFilter, TImportResponseItem } from '@/services/models/import';
import { FilterOut } from '@/services/mappers/common';
import { EImportStatus } from '@/services/enums/import';

export class ImportFilterOut extends FilterOut<TImportInnerFilter> {
  @Mapper.catch()
  get process_name() {
    return this.get(({ processName }) => processName).required.value;
  }

  toJSON() {
    return { ...super.toJSON(), process_name: this.process_name };
  }
}

export class ImportItem extends Mapper<TImportResponseItem> {
  static readonly StatusToName = {
    [EImportStatus.NEW]: 'Новый',
    [EImportStatus.PROCESSING]: 'В процессе',
    [EImportStatus.ERROR]: 'Ошибка импорта',
    [EImportStatus.FINISHED]: 'Загружено',
  };

  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get name() {
    return this.get(({ fileName }) => fileName).required.value;
  }

  @Mapper.catch()
  get uploadDate() {
    return this.get(({ created }) => created).required.date().value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ status }) => this.$mapStatus(status)).required.value;
  }

  @Mapper.catch()
  get result() {
    return this.get(({ result }) => result).optional.value;
  }

  @Mapper.catch()
  get errors() {
    return this.get(({ errors }) => errors || []).required.value;
  }

  private $mapStatus(status: EImportStatus) {
    return ImportItem.StatusToName[status] || '???';
  }

  toJSON() {
    return {
      id: this.id,
      name: this.name,
      uploadDate: this.uploadDate,
      status: this.status,
      result: this.result,
      errors: this.errors,
    };
  }
}
