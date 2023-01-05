import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {AddLeavingComponent} from './add-leaving.component';

describe('PetsProfileAppointmentsAddComponent', () => {
  let component: AddLeavingComponent;
  let fixture: ComponentFixture<AddLeavingComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [AddLeavingComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddLeavingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
