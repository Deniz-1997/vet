import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {BreedFormComponent} from './breed-form.component';

describe('BreedFormComponent', () => {
  let component: BreedFormComponent;
  let fixture: ComponentFixture<BreedFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [BreedFormComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BreedFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
