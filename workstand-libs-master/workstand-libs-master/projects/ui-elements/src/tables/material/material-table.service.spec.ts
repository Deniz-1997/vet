import { TestBed } from '@angular/core/testing';

import { MaterialTableService } from './material-table.service';

describe('BlockyTableService', () => {
  let service: MaterialTableService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(MaterialTableService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
