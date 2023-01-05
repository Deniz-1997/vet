import { TestBed } from '@angular/core/testing';

import { ListItemContentService } from './listItemContent.service';

describe('ListItemContentService', () => {
  let service: ListItemContentService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ListItemContentService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
