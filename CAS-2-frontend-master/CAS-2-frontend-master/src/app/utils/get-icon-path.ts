import {allowedFileTypes, iconsPath} from '../common/config';

export function getIconPathByType(mimeType: string): string {
  return allowedFileTypes[mimeType] ? iconsPath + 'format_icons/' + allowedFileTypes[mimeType].fileIcon : '';
}

export function getIconPath(icon: string, type: string = 'other'): string {
  return iconsPath + type + '/' + icon + '.svg';
}
