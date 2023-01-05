import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MeasurementUnitsListComponent } from './measurement-units-list.component';

describe('MeasurementUnitsListComponent', () => {
  let component: MeasurementUnitsListComponent;
  let fixture: ComponentFixture<MeasurementUnitsListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MeasurementUnitsListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MeasurementUnitsListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
