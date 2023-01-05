import {constructByInterface} from '../../utils/construct-by-interface';

export interface UploadedFileInterface {
  file?: string | File;
  id?: number;
  mimeType?: string;
  name?: string;
  relativePath?: string;
  path?: string;
  size?: number;
}

export class UploadedFileModel implements UploadedFileInterface {
  file: string | File;
  id: number;
  mimeType: string;
  name: string;
  relativePath: string;
  path: string;
  size: number;

  constructor(o?: UploadedFileInterface) {
    constructByInterface(o, this);
  }
}
