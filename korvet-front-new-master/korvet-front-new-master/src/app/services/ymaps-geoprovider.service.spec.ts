import {TestBed} from '@angular/core/testing';

import {YmapsGeoproviderService} from './ymaps-geoprovider.service';

describe('YmapsGeoproviderService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: YmapsGeoproviderService = TestBed.get(YmapsGeoproviderService);
    expect(service).toBeTruthy();
  });
});
