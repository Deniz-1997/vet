import { TestBed } from '@angular/core/testing';

import { ListItemService } from './listItem.service';

describe('ListItemService', () => {
  let service: ListItemService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ListItemService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
