<?php
/**
 * @package Wtfz_TmdbPackage
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2014, Michael Roterman
 */
namespace Wtfz\TmdbPackage;

use Tmdb\Client as TmdbClient;
use Tmdb\Repository\AccountRepository;
use Tmdb\Repository\AuthenticationRepository;
use Tmdb\Repository\CertificationRepository;
use Tmdb\Repository\ChangesRepository;
use Tmdb\Repository\CollectionRepository;
use Tmdb\Repository\CompanyRepository;
use Tmdb\Repository\ConfigurationRepository;
use Tmdb\Repository\CreditsRepository;
use Tmdb\Repository\DiscoverRepository;
use Tmdb\Repository\FindRepository;
use Tmdb\Repository\GenreRepository;
use Tmdb\Repository\GuestSessionRepository;
use Tmdb\Repository\JobsRepository;
use Tmdb\Repository\KeywordRepository;
use Tmdb\Repository\ListRepository;
use Tmdb\Repository\MovieRepository;
use Tmdb\Repository\NetworkRepository;
use Tmdb\Repository\PeopleRepository;
use Tmdb\Repository\ReviewRepository;
use Tmdb\Repository\SearchRepository;
use Tmdb\Repository\TimezoneRepository;
use Tmdb\Repository\TvEpisodeRepository;
use Tmdb\Repository\TvRepository;
use Tmdb\Repository\TvSeasonRepository;

class Tmdb extends TmdbClient {
    /**
     * @return AccountRepository
     */
    public function getAccountRepository()
    {
        return new AccountRepository($this);
    }

    /**
     * @return AuthenticationRepository
     */
    public function getAuthenticationRepository()
    {
        return new AuthenticationRepository($this);
    }

    /**
     * @return CertificationRepository
     */
    public function getCertificationRepository()
    {
        return new CertificationRepository($this);
    }

    /**
     * @return ChangesRepository
     */
    public function getChangesRepository()
    {
        return new ChangesRepository($this);
    }

    /**
     * @return CollectionRepository
     */
    public function getCollectionRepository()
    {
        return new CollectionRepository($this);
    }

    /**
     * @return CompanyRepository
     */
    public function getCompanyRepository()
    {
        return new CompanyRepository($this);
    }

    /**
     * @return ConfigurationRepository
     */
    public function getConfigurationRepository()
    {
        return new ConfigurationRepository($this);
    }

    /**
     * @return CreditsRepository
     */
    public function getCreditsRepository()
    {
        return new CreditsRepository($this);
    }

    /**
     * @return DiscoverRepository
     */
    public function getDiscoverRepository()
    {
        return new DiscoverRepository($this);
    }

    /**
     * @return FindRepository
     */
    public function getFindRepository()
    {
        return new FindRepository($this);
    }

    /**
     * @return GenreRepository
     */
    public function getGenreRepository()
    {
        return new GenreRepository($this);
    }

    /**
     * @return GuestSessionRepository
     */
    public function getGuestSessionRepository()
    {
        return new GuestSessionRepository($this);
    }

    /**
     * @return JobsRepository
     */
    public function getJobsRepository()
    {
        return new JobsRepository($this);
    }

    /**
     * @return KeywordRepository
     */
    public function getKeywordRepository()
    {
        return new KeywordRepository($this);
    }

    /**
     * @return ListRepository
     */
    public function getListRepository()
    {
        return new ListRepository($this);
    }

    /**
     * @return MovieRepository
     */
    public function getMovieRepository()
    {
        return new MovieRepository($this);
    }

    /**
     * @return NetworkRepository
     */
    public function getNetworkRepository()
    {
        return new NetworkRepository($this);
    }

    /**
     * @return PeopleRepository
     */
    public function getPeopleRepository()
    {
        return new PeopleRepository($this);
    }

    /**
     * @return ReviewRepository
     */
    public function ReviewRepository()
    {
        return new ReviewRepository($this);
    }

    /**
     * @return SearchRepository
     */
    public function SearchRepository()
    {
        return new SearchRepository($this);
    }

    /**
     * @return TimezoneRepository
     */
    public function getTimezoneRepository()
    {
        return new TimezoneRepository($this);
    }

    /**
     * @return TvEpisodeRepository
     */
    public function getTvEpisodeRepository()
    {
        return new TvEpisodeRepository($this);
    }

    /**
     * @return TvRepository
     */
    public function getTvRepository()
    {
        return new TvRepository($this);
    }

    /**
     * @return TvSeasonRepository
     */
    public function getTvSeasonRepository()
    {
        return new TvSeasonRepository($this);
    }
}
