import {TestBed} from '@angular/core/testing';

import {ViewService} from './view.service';

describe('OwnersViewService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ViewService = TestBed.get(ViewService);
    expect(service).toBeTruthy();
  });
});
