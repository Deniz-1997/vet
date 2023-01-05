import {DateParsePipe} from './date-parse.pipe';
import {DatePipe} from '@angular/common';

describe('DateParsePipe', () => {
  it('create an instance', () => {
    const date = new DatePipe('ru');
    const pipe = new DateParsePipe(date);
    expect(pipe).toBeTruthy();
  });
});
