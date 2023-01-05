import { TestBed } from '@angular/core/testing';

import { ListItemIconService } from './listItemIcon.service';

describe('ListItemIconService', () => {
  let service: ListItemIconService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ListItemIconService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
