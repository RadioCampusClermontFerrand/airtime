/*------------------------------------------------------------------------------

    Copyright (c) 2004 Media Development Loan Fund
 
    This file is part of the LiveSupport project.
    http://livesupport.campware.org/
    To report bugs, send an e-mail to bugs@campware.org
 
    LiveSupport is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
  
    LiveSupport is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with LiveSupport; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 
 
    Author   : $Author: maroy $
    Version  : $Revision: 1.1 $
    Location : $Source: /home/paul/cvs2svn-livesupport/newcvsrepo/livesupport/products/scheduler/src/PlaylistEventContainer.h,v $

------------------------------------------------------------------------------*/
#ifndef PlaylistEventContainer_h
#define PlaylistEventContainer_h

#ifndef __cplusplus
#error This is a C++ include file
#endif


/* ============================================================ include files */

#ifdef HAVE_CONFIG_H
#include "configure.h"
#endif

#include "LiveSupport/Core/StorageClientInterface.h"
#include "LiveSupport/PlaylistExecutor/AudioPlayerInterface.h"
#include "LiveSupport/EventScheduler/EventContainerInterface.h"

#include "ScheduleInterface.h"


namespace LiveSupport {
namespace Scheduler {

using namespace boost::posix_time;

using namespace LiveSupport;
using namespace LiveSupport::Core;
using namespace LiveSupport::PlaylistExecutor;
using namespace LiveSupport::EventScheduler;

/* ================================================================ constants */


/* =================================================================== macros */


/* =============================================================== data types */

/**
 *  An event container holding the scheduled playlists.
 *
 *  @author  $Author: maroy $
 *  @version $Revision: 1.1 $
 */
class PlaylistEventContainer : public virtual EventContainerInterface
{
    private:
        /**
         *  The storage containing the playlists to play.
         */
        Ptr<StorageClientInterface>::Ref    storage;

        /**
         *  The schedule interface to get the events from.
         */
        Ptr<ScheduleInterface>::Ref         schedule;

        /**
         *  The audio player to play the playlist with.
         */
        Ptr<AudioPlayerInterface>::Ref      audioPlayer;


    public:
        /**
         *  Constructor.
         *
         *  @param storage the storage containing the playlist and related
         *         audio clips
         *  @param schedule the schedule to get the events from.
         *  @param audioPlayer the audio player to play the playlists with.
         */
        PlaylistEventContainer(Ptr<StorageClientInterface>::Ref storage,
                               Ptr<ScheduleInterface>::Ref      schedule,
                               Ptr<AudioPlayerInterface>::Ref   audioPlayer)
                                                                    throw ();

        /**
         *  A virtual destructor, as this class has virtual functions.
         */
        virtual
        ~PlaylistEventContainer(void)                           throw ()
        {
        }

        /**
         *  Return the first event after the specified timepoint.
         *
         *  @param when return the first event after this timepoint,
         *  @return the first event to schedule after the specified
         *          timepoint. may be a reference to 0, if currently
         *          there are no known events after the specified time.
         */
        virtual Ptr<ScheduledEventInterface>::Ref
        getNextEvent(Ptr<ptime>::Ref    when)               throw ();
};


/* ================================================= external data structures */


/* ====================================================== function prototypes */


} // namespace Scheduler
} // namespace LiveSupport


#endif // PlaylistEventContainer_h

