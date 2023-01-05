import { TestBed } from '@angular/core/testing';

import { ListItemTitleService } from './listItemTitle.service';

describe('ListItemTitleService', () => {
  let service: ListItemTitleService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ListItemTitleService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
