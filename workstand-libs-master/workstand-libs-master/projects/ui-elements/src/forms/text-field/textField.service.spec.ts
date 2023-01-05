import { TestBed } from '@angular/core/testing';

import { TextFieldService } from './textField.service';

describe('TextFieldService', () => {
  let service: TextFieldService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(TextFieldService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
