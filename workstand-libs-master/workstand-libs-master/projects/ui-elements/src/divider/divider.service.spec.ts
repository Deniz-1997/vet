import { TestBed } from '@angular/core/testing';

import { DividerService } from './divider.service';

describe('DividerService', () => {
  let service: DividerService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(DividerService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
