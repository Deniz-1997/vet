import { TestBed } from '@angular/core/testing';

import { ReportTableService } from './report-table.service';

describe('ReportTableService', () => {
  let service: ReportTableService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ReportTableService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
