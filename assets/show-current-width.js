"use strict";

class ShowCurrentWidth {

	constructor() {
		this.timeoutWidth = undefined;
		this.timeoutCountUp = undefined;
		this.delay = ShowCurrentWidthVariables.animation_timeout;
		this.widthFrom = 0;
		this.widthTo = window.innerWidth;
		this.widthNow = 0;
		this.countUpProceeding = false;
		window.addEventListener('load', () => { this.showWidth(); });
		window.addEventListener('load', () => { this.showWidthDelay(); });
	}

	showWidth() {
		this.widthTo = window.innerWidth;
		if(document.getElementById('wp-admin-bar-show-current-width') == null) {
			return;
		}
		if(1 == ShowCurrentWidthVariables.animation_show) {
			// Animation
			this.countUpProceeding = true;
			this.showWidthWithAnimation();
		} else {
			// No animation
			this.showWidthWithoutAnimation();
		}
	}

	showWidthWithAnimation() {
		let countUpUnit = Math.abs(this.widthTo - this.widthFrom) / 30;

		this.showWidthCore(this.widthNow);

		if (!this.countUpProceeding) {
			clearTimeout(this.timeoutCountUp);
			this.widthFrom = this.widthTo;
			return;
		}

		if (this.widthNow > this.widthTo) {
			this.widthNow -= countUpUnit;
		} else if (this.widthNow < this.widthTo) {
			this.widthNow += countUpUnit;
		}

		if (Math.abs(this.widthNow - this.widthTo) <= countUpUnit) {
			this.widthNow = this.widthTo;
			this.countUpProceeding = false;
		}

		this.timeoutCountUp = setTimeout(() => {
			this.showWidthWithAnimation();
		}, 7);
	}

	showWidthWithoutAnimation() {
		this.showWidthCore(this.widthTo);
	}

	showWidthCore(width) {
		let breakPointShort = undefined;
		let breakPointLong = undefined;
		let breakPointCurrent = new Array();
		let breakPointCurrentIcon = '»';
		let icon = undefined;
		ShowCurrentWidthVariables.breakpoints_definition.split('\n').forEach((line, index) => {
			let items = line.trim().split(/\s*,\s*/);
			if (items[0] <= this.widthNow && this.widthNow < items[1]) {
				breakPointShort = items[2];
				breakPointLong = items[3];
				breakPointCurrent[index] = true;
			} else {
				breakPointCurrent[index] = false;
			}
		});
		document.querySelector('#wp-admin-bar-show-current-width .ab-icon .width').textContent = Math.round(width);
		document.querySelector('#wp-admin-bar-show-current-width .ab-label .width').textContent = Math.round(width);
		if (1 == ShowCurrentWidthVariables.breakpoints_show) {
			document.querySelector('#wp-admin-bar-show-current-width .breakpoint').textContent = breakPointShort;
			document.querySelector('#wp-admin-bar-show-current-width-breakpoint .breakpoint').textContent = breakPointLong;
			breakPointCurrent.forEach((element, index) => {
				if( element ) {
					icon = breakPointCurrentIcon;
				} else {
					icon = '';
				}
				document.querySelector('#wp-admin-bar-show-current-width-breakpoint-' + index + ' .icon').textContent = icon;
			});
		}
	}

	showWidthDelay() {
		window.addEventListener('resize', () => {
			clearTimeout(this.timeoutWidth);
			this.timeoutWidth = setTimeout(() => {
				this.showWidth();
			}, this.delay);
		}, false);
	}

}

new ShowCurrentWidth();
