import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { IconProfileComponent } from './icon.component';

describe('IconProfileComponent', () => {
  let component: IconProfileComponent;
  let fixture: ComponentFixture<IconProfileComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ IconProfileComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IconProfileComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
