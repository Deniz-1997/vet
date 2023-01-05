import {allowedFileTypes, iconsPath} from '../common/config';

export function getIconPathByType(mimeType: string): string {
  return allowedFileTypes[mimeType] ? iconsPath + allowedFileTypes[mimeType].fileIcon : '';
}

export function getIconPath(icon: string): string {
  return iconsPath + icon;
}
