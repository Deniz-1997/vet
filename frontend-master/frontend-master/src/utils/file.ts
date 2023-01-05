import axios from 'axios';

export function invokeFileDownloadFromBlob(blob: Blob, fileNameAndExtension: string): void {
  const href = URL.createObjectURL(blob);

  const link = document.createElement('a');

  link.download = fileNameAndExtension;
  link.href = href;
  link.click();

  URL.revokeObjectURL(link.href);
}

export function dataURLtoFile(dataURI: string, filename: string, meme: string): File {
  const byteString = atob(dataURI);

  const ab = new ArrayBuffer(byteString.length);
  const ia = new Uint8Array(ab);
  for (let i = 0; i < byteString.length; i++) {
    ia[i] = byteString.charCodeAt(i);
  }

  return new File([ab], filename, { type: meme });
}

type TSort = {
  property: string;
  direction: 'ASC' | 'DESC';
};

type TFilter = {
  filter?: string;
  actual?: boolean;
  pageable?: {
    pageSize?: number;
    pageNumber?: number;
    sort?: TSort[];
  };
  is_processor?: boolean;
};

type TOptions = {
  path: string;
  filter: TFilter;
  defaultName?: string;
  method?: string;
};

export async function showReport({ path, filter, defaultName, method = 'post' }: TOptions) {
  const options: any[] = [path];
  if (method === 'post') {
    options.push(filter, { responseType: 'blob' });
  } else {
    options.push({ params: filter, responseType: 'blob' });
  }
  const response = await axios[method](...options);
  const filename = response.headers['x-filename'] || defaultName;
  const type = response.headers['content-type'] || 'application/vnd.ms-excel';
  const file = new File([response.data], filename, { type });

  invokeFileDownloadFromBlob(file, filename);
}

export async function showFile({ path, method = 'get' }: any) {
  const options: any[] = [path];
  options.push({ responseType: 'blob' });
  const response = await axios[method](...options);
  const filename = response.headers['x-filename'];
  const type = response.headers['content-type'] || 'application/vnd.pdf';
  const file = new File([response.data], filename, { type });

  invokeFileDownloadFromBlob(file, filename);
}