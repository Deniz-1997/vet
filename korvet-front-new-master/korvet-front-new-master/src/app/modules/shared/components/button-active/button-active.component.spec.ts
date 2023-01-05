import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { ButtonActiveComponent } from './button-active.component';

describe('ButtonActiveComponent', () => {
  let component: ButtonActiveComponent;
  let fixture: ComponentFixture<ButtonActiveComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ ButtonActiveComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ButtonActiveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
