import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {TemperatureWeightAddFormComponent} from './temperature-weight-add-form.component';

describe('TemperatureWeightAddFormComponent', () => {
  let component: TemperatureWeightAddFormComponent;
  let fixture: ComponentFixture<TemperatureWeightAddFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [TemperatureWeightAddFormComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TemperatureWeightAddFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
