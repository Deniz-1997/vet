export const tokenConfig = {
  name: 'token',
};
export const iconsPath = '/assets/icons/';
export const allowedFileTypes: { [mimeType: string]: { fileIcon?: string } } = {
  'application/pdf': {fileIcon: 'pdf.svg'},
  'application/zip': {fileIcon: 'zip.svg'},
  'application/vnd.rar': {fileIcon: 'rar.svg'},
  'application/x-7z-compressed': {fileIcon: '7z.svg'},
  'application/x-tar': {fileIcon: 'TAR.svg'},
  'application/x-compressed-tar': {fileIcon: 'TAR.svg'},
  'application/msword': {fileIcon: 'doc.svg'},
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document': {fileIcon: 'docx.svg'},
  'application/rtf': {fileIcon: 'rtf.svg'},
  'application/vnd.ms-excel': {fileIcon: 'xls.svg'},
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': {fileIcon: 'xlsx.svg'},
  'image/gif': {fileIcon: 'gif.svg'},
  'image/jpeg': {fileIcon: 'jpg.svg'},
  'image/png': {fileIcon: 'png.svg'},
  'image/bmp': {fileIcon: 'bmp.svg'},
  'text/plain': {fileIcon: 'txt.svg'},
  'text/html': {fileIcon: 'txt.svg'},
  'text/csv': {fileIcon: 'csv.svg'},
};
