import { TestBed } from '@angular/core/testing';

import { ListItemGroupService } from './listItemGroup.service';

describe('ListItemGroupService', () => {
  let service: ListItemGroupService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ListItemGroupService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
