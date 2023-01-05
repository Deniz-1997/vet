import {TestBed} from '@angular/core/testing';

import {BlockScreenService} from './block-screen.service';

describe('BlockScreenService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: BlockScreenService = TestBed.get(BlockScreenService);
    expect(service).toBeTruthy();
  });
});
