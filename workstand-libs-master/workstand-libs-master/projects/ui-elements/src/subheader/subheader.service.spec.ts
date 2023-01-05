import { TestBed } from '@angular/core/testing';

import { SubheaderService } from './subheader.service';

describe('SubheaderService', () => {
  let service: SubheaderService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(SubheaderService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
