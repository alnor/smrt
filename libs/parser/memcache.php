<?php

class BlockingMemcache extends Memcache{
	
	/**
	 * 
	 * Получает данные, предварительно дождавшись снятия блокировки, если такая есть.
	 * @param string $key
	 */

	public function getWithUnlock($key){
		
		$lock = $this->get($key . '_qry_lock');

		if ($lock):
		
			$itercount = 0;
			
			do {
				sleep(0.3);
				$lock = $this->get($key . '_qry_lock');
				$itercount++;
				
				if ($itercount==30){
					$this->delBlock($key);
					break;
				}
				
			} while ( $lock );
				
		endif;

		return $this->get($key);
	}
	
	/**
	 * 
	 * Получает данные, предварительно дождавшись снятия блокировки, если такая есть.
	 * Перед возвратом данных ставит блокировку
	 * @param string $key
	 */

	public function getAfterLock($key){
		
		$lock = $this->get($key . '_qry_lock');
		
		if ($lock):
		
			$itercount = 0;
		
			do {
				sleep(0.3);
				$lock = $this->get($key . '_qry_lock');
				$itercount++;
				
				if ($itercount==30){
					$this->delBlock($key);
					break;
				}
								
			} while ( $lock );
				
		endif;
		
		$this->set($key . '_qry_lock', true, false, 2);
		
		return $this->get($key);
	}	
	
	/**
	 * 
	 * Ставит блокировку по ключу
	 * @param string $key
	 */
	
	function setBlock($key){
		
		$this->set($key . '_qry_lock', true, false, 2);
		
		return true;
	}
	
	/**
	 * 
	 * Удаляет блокировку по ключу
	 * @param string $key
	 */
	
	function delBlock($key){
		
		$this->delete($key . '_qry_lock');

	}	

}
?>