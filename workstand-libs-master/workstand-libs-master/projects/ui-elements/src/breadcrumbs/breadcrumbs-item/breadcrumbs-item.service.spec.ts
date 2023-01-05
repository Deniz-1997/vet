import { TestBed } from '@angular/core/testing';

import { BreadcrumbsItemService } from './breadcrumbs-item.service';

describe('BreadcrumbsItemService', () => {
  let service: BreadcrumbsItemService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(BreadcrumbsItemService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
