import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {OwnerFarmFormComponent} from './owner-farm-form.component';

describe('OwnerFarmFormComponent', () => {
  let component: OwnerFarmFormComponent;
  let fixture: ComponentFixture<OwnerFarmFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [OwnerFarmFormComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OwnerFarmFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
