import {TestBed} from '@angular/core/testing';

import {NoAccessService} from './no-access.service';

describe('AccessService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: NoAccessService = TestBed.get(NoAccessService);
    expect(service).toBeTruthy();
  });
});
