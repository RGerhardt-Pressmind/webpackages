<?php

namespace system\core\Transfer\Adapter;

interface AdapterInterface
{
	/**
	 * @param array $config
	 * @return bool
	 */
	function connection(array $config): bool;

	/**
	 * @param string $remotePath
	 * @param string $localePath
	 * @return bool
	 */
	function getFile(string $remotePath, string $localePath): bool;

	/**
	 * @param array $remoteFiles ['remotePath' => 'localePath']
	 * @return bool
	 */
	function getFiles(array $remoteFiles): bool;

	/**
	 * @param string $localePath
	 * @param string $remotePath
	 * @return bool
	 */
	function putFile(string $localePath, string $remotePath): bool;

	/**
	 * @param array $files ['localePath' => 'remotePath']
	 * @return bool
	 */
	function putFiles(array $files): bool;

	/**
	 * @param string $content
	 * @param string $remotePath
	 * @return bool
	 */
	function putContent(string $content, string $remotePath): bool;

	/**
	 * @param string $removePath
	 * @return bool
	 */
	function removeFile(string $removePath): bool;

	/**
	 * @param array $files
	 * @return bool
	 */
	function removeFiles(array $files): bool;

	/**
	 * @return array
	 */
	function getErrorLogs(): array;

	/**
	 * @return mixed
	 */
	function getRessource():mixed;
}
