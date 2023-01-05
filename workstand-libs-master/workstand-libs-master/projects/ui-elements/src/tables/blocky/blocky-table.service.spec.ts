import { TestBed } from '@angular/core/testing';

import { BlockyTableService } from './blocky-table.service';

describe('BlockyTableService', () => {
  let service: BlockyTableService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(BlockyTableService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
