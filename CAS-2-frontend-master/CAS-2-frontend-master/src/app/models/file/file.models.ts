import {constructByInterface} from '../../utils/construct-by-interface';
import {ElementDefInterface} from '../element.def.models';
import {ReferenceFileTypeInterface, ReferenceFileTypeModel} from '../reference/reference.file.type.models';
import {UploadedFileInterface, UploadedFileModel} from './uploaded-file.models';

export interface FileInterface extends ElementDefInterface {
  type?: ReferenceFileTypeInterface;
  format?: string;
  size?: number;
  title?: string;
  name: string;
  createdAt?: string;
  uploadedFile: UploadedFileInterface;
  deleted?: boolean;
  id: number;
}

export class FileModel implements FileInterface {
  type: ReferenceFileTypeModel;
  format: string;
  size: number;
  title: string;
  name: string;
  createdAt: string;
  uploadedFile: UploadedFileModel;
  deleted: boolean;
  id: number;

  constructor(o?: FileInterface) {
    constructByInterface(o, this, {
      type: ReferenceFileTypeModel,
      uploadedFile: UploadedFileModel,
    });
  }
}

export class FileOwnerModel implements FileInterface {
  type: ReferenceFileTypeModel;
  format: string;
  size: number;
  title: string;
  name: string;
  createdAt: string;
  uploadedFile: UploadedFileModel;
  deleted: boolean;
  id: number;

  constructor(o?: FileInterface) {
    constructByInterface(o, this, {
      type: ReferenceFileTypeModel,
      uploadedFile: UploadedFileModel,
    });
  }
}

export type FileModelType = FileOwnerModel | FileModel;
