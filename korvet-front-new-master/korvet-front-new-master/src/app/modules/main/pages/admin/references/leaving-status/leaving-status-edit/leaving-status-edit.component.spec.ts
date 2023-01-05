import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LeavingStatusEditComponent } from './leaving-status-edit.component';

describe('LeavingStatusEditComponent', () => {
  let component: LeavingStatusEditComponent;
  let fixture: ComponentFixture<LeavingStatusEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ LeavingStatusEditComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(LeavingStatusEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
