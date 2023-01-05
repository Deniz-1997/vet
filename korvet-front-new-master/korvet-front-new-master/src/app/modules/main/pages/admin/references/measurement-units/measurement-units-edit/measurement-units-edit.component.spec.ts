import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MeasurementUnitsEditComponent } from './measurement-units-edit.component';

describe('MeasurementUnitsEditComponent', () => {
  let component: MeasurementUnitsEditComponent;
  let fixture: ComponentFixture<MeasurementUnitsEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MeasurementUnitsEditComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MeasurementUnitsEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
