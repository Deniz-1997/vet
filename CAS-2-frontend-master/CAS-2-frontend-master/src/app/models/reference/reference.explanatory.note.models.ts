import {constructByInterface} from '../../utils/construct-by-interface';
import {UploadedFileModel} from '../file/uploaded-file.models';
import {UserModel} from '../../api/auth/auth.models';

export interface ReportExplanatoryNoteInterface {
  id: number;
  comment: string;
  file: UploadedFileModel;
  user: UserModel;
  reportData: any;
  deleted: boolean;
  createdAt: string;
  updateddAt: string;
}

export class ReportExplanatoryNoteModel implements ReportExplanatoryNoteInterface {
  id: number;
  comment: string;
  file: UploadedFileModel;
  user: UserModel;
  reportData: any;
  deleted: boolean;
  createdAt: string;
  updateddAt: string;


  constructor(o?: ReportExplanatoryNoteInterface) {
    constructByInterface(o, this);
  }
}
