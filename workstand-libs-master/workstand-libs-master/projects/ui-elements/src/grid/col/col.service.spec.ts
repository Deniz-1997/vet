import { TestBed } from '@angular/core/testing';

import { ColService } from './col.service';

describe('GridsService', () => {
  let service: ColService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ColService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
