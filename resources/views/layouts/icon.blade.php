@php($name = $name ?? 'grid')
@switch($name)
@case('grid') <svg viewBox="0 0 24 24"><rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/></svg> @break
@case('box') <svg viewBox="0 0 24 24"><path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="M4 7v10l8 4 8-4V7M12 11v10"/></svg> @break
@case('cube') <svg viewBox="0 0 24 24"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z"/><path d="m4.5 7.5 7.5 4 7.5-4M12 11.5V21"/></svg> @break
@case('move') <svg viewBox="0 0 24 24"><path d="M7 7h10M7 17h10M5 12h14M8 4l-3 3 3 3M16 14l3 3-3 3"/></svg> @break
@case('warehouse') <svg viewBox="0 0 24 24"><path d="m3 10 9-6 9 6v10H3V10Z"/><path d="M7 20v-6h4v6M13 14h4v6M7 10h10"/></svg> @break
@case('sales') <svg viewBox="0 0 24 24"><path d="M5 5h14v14H5z"/><path d="m8 15 2-3 2 2 3-5"/></svg> @break
@case('pos') <svg viewBox="0 0 24 24"><rect x="4" y="5" width="16" height="14" rx="2"/><path d="M7 9h10M8 13h2m2 0h2m2 0h2M8 16h2m2 0h2"/></svg> @break
@case('cart') <svg viewBox="0 0 24 24"><path d="M4 5h2l2 10h9l2-7H7"/><circle cx="10" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/></svg> @break
@case('users') <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 20c.5-4 2.5-6 6-6s5.5 2 6 6M16 5a3 3 0 0 1 0 6M17 14c2.5.5 3.5 2 4 4"/></svg> @break
@case('chart') <svg viewBox="0 0 24 24"><path d="M5 19V5M5 19h14"/><path d="m8 15 3-4 3 2 4-6"/></svg> @break
@case('customer') <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"/><path d="M5 20c.7-4 3-6 7-6s6.3 2 7 6"/></svg> @break
@case('return') <svg viewBox="0 0 24 24"><path d="M9 7H5l4-4M5 7c6-1 11 1 12 7 .4 2.4-.5 4.3-2 6"/></svg> @break
@case('wallet') <svg viewBox="0 0 24 24"><path d="M4 6h15a2 2 0 0 1 2 2v10H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h13"/><path d="M16 13h5"/></svg> @break
@case('key') <svg viewBox="0 0 24 24"><circle cx="8" cy="15" r="4"/><path d="m11 12 8-8m-3 3 2 2m-5-5 2 2"/></svg> @break
@case('audit') <svg viewBox="0 0 24 24"><path d="M6 3h12v18H6z"/><path d="M9 7h6M9 11h6M9 15h4"/></svg> @break
@default <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/></svg>
@endswitch
