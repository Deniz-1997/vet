import {PetsModule} from './pets.module';

describe('PetsModule', () => {
  let petsModule: PetsModule;

  beforeEach(() => {
    petsModule = new PetsModule();
  });

  it('should create an instance', () => {
    expect(petsModule).toBeTruthy();
  });
});
